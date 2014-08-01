<?php

use Illuminate\Filesystem\Filesystem;
class CopyController extends BaseController
{
    public function getIndex()
    {
        $lang = Input::get('lang', Lang::getLocale());

        $fileSystem = new Filesystem();

        $namespaces = $fileSystem->files(app_path() . '/lang/' . $lang . '/');
        $translationFiles = array();

        foreach($namespaces as $file)
        {
            $namespace = array(
                'path' => $file,
                'name' => basename($file, '.php'),
                'translations' => array()
            );

            $translationData = self::parseTranslations($file);
            foreach($translationData as $key => $translation)
            {
                if(is_array($translation))
                {
                    foreach($translation as $subKey => $subTranslation)
                    {
                        $data = array(
                            'key' => $namespace['name'] . '.' . $key . '.' . $subKey,
                            'value' => $subTranslation,
                            'name' => $key . ': ' . $subKey
                        );


                        $namespace['translations'][] = $data;
                    }
                }
                else
                {
                    $data = array(
                        'key' => $namespace['name'] . '.' . $key,
                        'value' => $translation,
                        'name' => $key
                    );

                    $namespace['translations'][] = $data;
                }
            }

            $translationFiles[] = $namespace;
        }

        return View::make('copy.edit')->with('namespaces', $translationFiles);
    }

    public function postIndex()
    {
        dd(Input::all());

        $bitbucket = new Bitbucket\API\Api();
        $bitbucket->requestPost('repositories.pullrequests');

    }

    public static function parseTranslations($path)
    {
        return require $path;
    }
}