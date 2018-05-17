<?php

namespace Drupal\registration_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Mail;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * RegisterForm class.for registering user.
 */
class RegisterForm extends Formbase {

  /**
   * Gets the language manager.
   *
   * @return \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructor to initialize the languageManager object.
   */
  public function __construct(LanguageManagerInterface $language_manager) {
    $this->languageManager = $language_manager;
  }

  /**
   * Create function for container.
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('language_manager'));
  }

  /**
   * Function to get the FormId.
   */
  public function getFormId() {
    return 'registration_form';
  }

  /**
   * Build the form to register user.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_name'] = [
      '#type' => 'textfield',
      '#title' => 'User Name',
      '#default_value' => '',
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
      '#prefix' => '<div id="user-name-result"></div>',
      '#ajax' => [
        'callback' => '::checkUserNameValidation',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ],
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => 'Email',
      '#required' => TRUE,
      '#prefix' => '<div id="user-email-result"></div>',
      '#ajax' => [
        'callback' => '::checkUserEmailValidation',
        'effect' => 'fade',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ],
    ];

    $form['password'] = [
      '#type' => 'password_confirm',
      // '#title' => 'Password',.
      '#size' => 25,
      '#required' => TRUE,
    ];

    $form['save'] = [
      '#type' => 'submit',
      '#value' => 'Register',
    ];

    return $form;
  }

  /**
   * Validate user_name function.
   */
  public function checkUserNameValidation(array $form, FormStateInterface $form_state) {

    $ajax_response = new AjaxResponse();

    if (user_load_by_name($form_state->getValue('user_name'))) {
      $text = 'UserName exists';
    }

    $ajax_response->addCommand(new HtmlCommand('#user-name-result', $text));
    return $ajax_response;
  }

  /**
   * Validate user_email function.
   */
  public function checkUserEmailValidation(array $form, FormStateInterface $form_state) {

    $ajax_response = new AjaxResponse();

    if (user_load_by_mail($form_state->getValue('email'))) {
      $text = 'UserEmail exists';
    }

    $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
    return $ajax_response;
  }

  /**
   * Submit the form to redirect to next panel page.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $language = $this->languageManager->getCurrentLanguage()->getId();
    $user = User::create();

    $user->setPassword($form_state->getValue('password'));
    $user->enforceIsNew();
    $user->setEmail($form_state->getValue('email'));
    $user->setUsername($form_state->getValue('user_name'));
    $user->set('init', $form_state->getValue('email'));
    $user->set('langcode', $language);
    $user->set('preferred_langcode', $language);
    $user->set('preferred_admin_langcode', $language);
    // $user->set('setting_name', 'setting_value');
    // $user->addRole('rid');.
    $user->activate();
    $user->save();

    $newMail = \Drupal::service('plugin.manager.mail');
    $params['password'] = $form_state->getValue('password');
    $params['user_name'] = $form_state->getValue('user_name');
    $params['body'] = "Hi,
                       This is a hook for sending mails in custom registration_module.
                       Your username: " . $params['email'] . "
                       Your password: " . $params['password'] .
                       "Thank You";

    $newMail->mail('registration_module', 'registerMail', $form_state->getValue('email'), 'en', $params,
                    $reply = NULL, $send = TRUE);

    user_login_finalize($user);
    // drupal_set_message("User with uid " . $user->id() . " saved!\n");.
    $url = Url::fromRoute('<front>');
    $form_state->setRedirectUrl($url);
  }

}
