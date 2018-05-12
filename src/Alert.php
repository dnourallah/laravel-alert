<?php

namespace DNourallah\LaravelAlert;

use DNourallah\LaravelAlert\Storage\SessionStore;

class Alert
{
    /**
     * Session storage.
     */
    protected $session;

    /**
     *
     * @var array
     */
    protected $config;

    /**
     *
     * @var array
     */
    protected $param;

    /**
     *
     * @var array
     */
    protected $cancel;

    const PLEASANT_ALERT_AUTOCLOSE = '6000';
    const BTN_CONFIRM_TEXT  = 'OK';
    const BTN_CONFIRM_COLOR = '#3085d6';
    const BTN_CANCEL_TEXT   = 'Cancel';
    const BTN_CANCEL_COLOR  = '#aaa';

    public function __construct(SessionStore $session)
    {
        $this->setDefaultConfig();

        $this->session = $session;
    }

    /**
     * Sets all default config options for an alert.
     *
     * @return void
     */
    protected function setDefaultConfig()
    {
        $this->config = [
            'timer' => env('PLEASANT_ALERT_AUTOCLOSE', self::PLEASANT_ALERT_AUTOCLOSE),
            'title' => '',
            'text' => '',
            'showConfirmButton' => false,
            'buttonsStyling' => true,
            'confirmButtonClass' => 'px-5 mx-2',
            'cancelButtonClass' => 'px-5 mx-2',
        ];
    }

    /**
     * Flash a message.
     *
     * @param  string $title
     * @param  string $text
     * @param  array  $type
     *
     * @return void
     */
    public function alert($title = '', $text = '', $type = null)
    {
        $this->config['title'] = $title;

        $this->config['text'] = $text;

        if (!is_null($type)) {
            $this->config['type'] = $type;
        }

        $this->flash();

        return $this;
    }

    /*
     **
     * Display a success typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function success($title = '', $text = '')
    {
        $this->alert($title, $text, 'success');

        return $this;
    }

    /*
     **
     * Display a info typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function info($title = '', $text = '')
    {
        $this->alert($title, $text, 'info');

        return $this;
    }

    /*
     **
     * Display a warning typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function warning($title = '', $text = '')
    {
        $this->alert($title, $text, 'warning');

        return $this;
    }

    /*
     **
     * Display a question typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function question($title = '', $text = '')
    {
        $this->alert($title, $text, 'question');

        return $this;
    }

    /*
     **
     * Display a error typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function error($title = '', $text = '')
    {
        $this->alert($title, $text, 'error');

        return $this;
    }

    /*
     **
     * Display a html typed alert message with html code.
     *
     * @param string $title
     * @param string $code
     * @param string $type
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function html($title, $code, $type)
    {
        $this->config['title'] = $title;

        $this->config['html'] = $code;

        if (!is_null($type)) {
            $this->config['type'] = $type;
        }

        $this->flash();

        return $this;
    }

    /*
     **
     * Display a parameter typed alert message with html code.
     *
     * @param string $title
     * @param string $code
     * @param string $type
     * @param array $confirm title text type
     * @param array $abord title text type
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function parameter($title = '', $code = '', $type = null, $confirm = [], $cancel = [])
    {
        $this->config['title'] = $title;

        $this->config['html'] = $code;

        if (!is_null($type)) {
            $this->config['type'] = $type;
        }

        if (isset($confirm)) {
            $this->paramConfirm($confirm[0], $confirm[1], $confirm[2]);
        }

        if (isset($cancel)) {
            $this->paramCancel($cancel[0], $cancel[1], $cancel[2]);
        }

        $this->flash();

        return $this;
    }

    /*
     **
     * Display confirm button on alert
     *
     * @param string $btnText
     * @param string $btnColor
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function paramConfirm($title = '', $text = '', $type = null, $btnText = self::BTN_CONFIRM_TEXT, $btnColor = self::BTN_CONFIRM_COLOR)
    {
        if (isset($title)) {
            $this->confirm['title'] = $title;
        }

        if (isset($text)) {
            $this->confirm['text'] = $text;
        }

        if (isset($type)) {
            $this->confirm['type'] = $type;
        }

        $this->showConfirmButton($btnText, $btnColor);

        return $this;
    }

    /*
     **
     * Display cancel button on alert
     *
     * @param string $btnText
     * @param string $btnColor
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function paramCancel($title = '', $text = '', $type = null, $btnText = self::BTN_CANCEL_TEXT, $btnColor = self::BTN_CANCEL_COLOR)
    {
        if (isset($title)) {
            $this->cancel['title'] = $title;
        }

        if (isset($text)) {
            $this->cancel['text'] = $text;
        }

        if (isset($type)) {
            $this->cancel['type'] = $type;
        }

        $this->showCancelButton($btnText, $btnColor);

        return $this;
    }

    /*
     **
     * Display a toast alert message with any typed.
     *
     * @param string $title
     * @param string $type
     * @param string $position
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function toast($title, $type = 'success', $position = 'top-right')
    {
        $this->config['toast'] = true;
        $this->config['title'] = $title;
        $this->config['showCloseButton'] = true;
        $this->config['type'] = $type;
        $this->config['position'] = $position;

        $this->flash();

        return $this;
    }

    /*
     **
     * Convert any alert method to Toast
     *
     * @param string $position
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function toToast($position = 'top-right')
    {
        $this->config['toast'] = true;
        $this->config['showCloseButton'] = false;
        $this->config['position'] = $position;

        $this->flash();

        return $this;
    }

    /*
     **
     * Add footer section to alert()
     *
     * @param string $code
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function footer($code)
    {
        $this->config['footer'] = $code;

        $this->flash();

        return $this;
    }

    /*
     **
     * make any alert persistent
     *
     * @param bool $showConfirmBtn
     * @param bool $showCloseBtn
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function persistent($showConfirmBtn = true, $showCloseBtn = false)
    {
        $this->config['allowEscapeKey'] = false;
        $this->config['allowOutsideClick'] = false;
        $this->removeTimer();

        if ($showConfirmBtn) {
            $this->showConfirmButton();
        }

        if ($showCloseBtn) {
            $this->showCloseButton();
        }

        $this->flash();

        return $this;
    }

    /*
     **
     * can any alert after param $milliseconds
     *
     * @param bool $milliseconds
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function autoClose($milliseconds = self::PLEASANT_ALERT_AUTOCLOSE)
    {
        $this->config['timer'] = $milliseconds;

        $this->flash();

        return $this;
    }

    /*
     **
     * Display confirm button on alert
     *
     * @param string $btnText
     * @param string $btnColor
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function showConfirmButton($btnText = self::BTN_CONFIRM_TEXT, $btnColor = self::BTN_CONFIRM_COLOR)
    {
        $this->config['showConfirmButton'] = true;
        $this->config['confirmButtonText'] = $btnText;
        $this->config['confirmButtonColor'] = $btnColor;
        $this->config['allowOutsideClick'] = false;
        $this->removeTimer();
        $this->flash();

        return $this;
    }

    /*
     **
     * Display cancel button on alert
     *
     * @param string $btnText
     * @param string $btnColor
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function showCancelButton($btnText = self::BTN_CANCEL_TEXT, $btnColor = self::BTN_CANCEL_COLOR)
    {
        $this->config['showCancelButton'] = true;
        $this->config['cancelButtonText'] = $btnText;
        $this->config['cancelButtonColor'] = $btnColor;
        $this->removeTimer();
        $this->flash();

        return $this;
    }

    /*
     **
     * Display close button on alert
     *
     * @param string $closeButtonAriaLabel
     *
     * @return DNourallah\LaravelAlert\Alert::alert();
     */
    public function showCloseButton($closeButtonAriaLabel = 'aria-label')
    {
        $this->config['showCloseButton'] = true;
        $this->config['closeButtonAriaLabel'] = $closeButtonAriaLabel;

        $this->flash();

        return $this;
    }

    /**
     * Remove the timer from config option.
     *
     * @return void
     */
    protected function removeTimer()
    {
        if (array_key_exists('timer', $this->config)) {
            unset($this->config['timer']);
        }
    }

    /**
     * Flash the config options for alert.
     *
     * @return void
     */
    public function flash()
    {
        foreach ($this->config as $key => $value) {
            $this->session->flash("alert.config.{$key}", $value);
        }

        $this->session->flash('alert.config', $this->buildConfig($this->config));

        if(isset($this->confirm)){
            foreach ($this->confirm as $key => $value) {
                $this->session->flash("alert.confirm.{$key}", $value);
            }
            $this->session->flash('alert.confirm', $this->buildConfig($this->confirm));
        }

        if(isset($this->cancel)){
            foreach ($this->cancel as $key => $value) {
                $this->session->flash("alert.cancel.{$key}", $value);
            }
            $this->session->flash('alert.cancel', $this->buildConfig($this->cancel));
        }

    }

    /**
     * Build Flash config options for flashing.
     *
     * @param $config
     * @return void
     */
    public function buildConfig($config)
    {
        return json_encode($config);
    }
}
