<?php

namespace Glorand\Model\Settings\Managers;

use Glorand\Model\Settings\Contracts\SettingsManagerContract;

/**
 * Class TableSettingsManager
 * @package Glorand\Model\Settings\Managers
 * @property  \Illuminate\Database\Eloquent\Model|\Glorand\Model\Settings\Traits\HasSettingsTable $model
 */
class TableSettingsManager extends AbstractSettingsManager
{
    /**
     * @param array $settings
     * @return \Glorand\Model\Settings\Contracts\SettingsManagerContract
     * @throws \Exception
     */
    public function apply(array $settings = []): SettingsManagerContract
    {
        $modelSettings = $this->model->modelSettings()->first();
        if (!count($settings)) {
            if ($modelSettings) {
                $modelSettings->delete();
            }
        } else {
            if (!$modelSettings) {
                $modelSettings = new confg('model_settings.settings_class_name');
                $modelSettings->setConnection($this->model->getConnectionName());
                $modelSettings->model()->associate($this->model);
            }
            $modelSettings->settings = $settings;
            $modelSettings->save();
        }

        cache()->forget($this->model->getSettingsCacheKey());

        return $this;
    }
}
