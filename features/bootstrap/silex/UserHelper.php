<?php

use OpenTribes\Core\Repository\User as UserRepository;
use OpenTribes\Core\Validator\Registration as RegistrationValidator;
use OpenTribes\Core\Service\PasswordHasher;
use OpenTribes\Core\Service\ActivationCodeGenerator;
use Behat\Mink\Mink;

require_once 'vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

class SilexUserHelper {

    private $userRepository;
    private $registrationValidator;
    private $passwordHasher;
    private $activationCodeGenerator;
    private $user;
    private $loggedInUsername;

    /**
     * @var \Behat\Mink\Element\DocumentElement
     */
    private $page;
    private $mink;
    private $sessionName;

    public function __construct(Mink $mink, UserRepository $userRepository, RegistrationValidator $registrationValidator, PasswordHasher $passwordHasher, ActivationCodeGenerator $activationCodeGenerator) {
        $this->userRepository          = $userRepository;
        $this->registrationValidator   = $registrationValidator;
        $this->passwordHasher          = $passwordHasher;
        $this->activationCodeGenerator = $activationCodeGenerator;
        $this->mink                    = $mink;
        $this->sessionName             = $this->mink->getDefaultSessionName();
    }

    private function loadPage() {
        $this->page = $this->mink->getSession($this->sessionName)->getPage();
    }

    public function processRegistration($username, $email, $emailConfirm, $password, $passwordConfirm, $termsAndConditions) {
        $this->loadPage();
        $this->page->fillField('username', $username);
        $this->page->fillField('email', $email);
        $this->page->fillField('emailConfirm', $emailConfirm);
        $this->page->fillField('password', $password);
        $this->page->fillField('passwordConfirm', $passwordConfirm);
        if ($termsAndConditions)
            $this->page->checkField('termsAndConditions');

        $this->page->pressButton('register');
    }

    public function createDummyAccount($username, $password, $email, $activationCode = null) {
        $userId   = $this->userRepository->getUniqueId();
        $password = $this->passwordHasher->hash($password);

        $this->user = $this->userRepository->create($userId, $username, $password, $email);
        if ($activationCode) {
            $this->user->setActivationCode($activationCode);
        }

        $this->userRepository->add($this->user);
    }

    public function assertRegistrationSucceed() {
        $this->mink->assertSession()->statusCodeEquals(200);
        $this->mink->assertSession()->elementNotExists('css', '.alert-danger');
    }

    public function processActivateAccount($username, $activationCode) {
   
    }

    public function getActivateAccountResponse() {
        $response         = new stdClass;
        $response->errors = array();
        return $response;
    }

    public function processLogin($username, $password) {
        $this->loadPage();
        $this->page->fillField('username', $username);
        $this->page->fillField('password', $password);
        $this->page->pressButton('login');
    }

    public function assertLoginSucceed() {
        $this->mink->assertSession()->statusCodeEquals(200);
        $this->mink->assertSession()->elementNotExists('css', '.alert-danger');
    }

    public function assertLoginFailed() {
        $this->mink->assertSession()->elementExists('css', '.alert-danger');
    }

    public function assertActivationSucceed() {
        $this->mink->assertSession()->statusCodeEquals(200);
        $this->mink->assertSession()->elementNotExists('css', '.alert-danger');
    }

    public function assertActivationFailed() {
        $this->mink->assertSession()->elementExists('css', '.alert-danger');
    }

    public function assertRegistrationFailed() {
        $this->mink->assertSession()->elementExists('css', '.alert-danger');
    }

    public function getRegistrationResponse() {
        $response         = new stdClass();
        $response->errors = array();
        return $response;
    }

    public function activateUser($username) {
        $user = $this->userRepository->findOneByUsername($username);
        $user->setActivationCode(null);
        $this->userRepository->replace($user);
    }

    public function loginAs($username) {
        $this->loggedInUsername = $username;
    }

    public function getLoggedInUsername() {
        return $this->loggedInUsername;
    }

}
