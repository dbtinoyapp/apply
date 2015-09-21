<?php

namespace Cv\Form;

use Zend\Form\Fieldset;

abstract class AbstractLanguageFieldset extends Fieldset {

    public function init() {

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'language',
            'attributes' => array(
                'title' => /* @translate */ 'Select Language',
                'class' => 'language-select'
            ),
            'options' => array(
                'label' => 'Select Language',
                'empty_option' => 'Please Select',
                'value_options' => array(
                  'Tagalog'  => 'Tagalog',
                  'English'  => 'English',
//                  'Abkhazian'  => 'Abkhazian',
//                  'Afrikaans'  => 'Afrikaans',
//                  'Albanian'  => 'Albanian',
//                  'Amharic'  => 'Amharic',
//                  'Arabic'  => 'Arabic',
//                  'Armenian'  => 'Armenian',
//                  'Assamese'  => 'Assamese',
//                  'Azerbaijani'  => 'Azerbaijani',
//                  'Basque'  => 'Basque',
//                  'Belarusian'  => 'Belarusian',
//                  'Bengali'  => 'Bengali',
//                  'Bosnian'  => 'Bosnian',
//                  'Breton'  => 'Breton',
//                  'Bulgarian'  => 'Bulgarian',
//                  'Burmese'  => 'Burmese',
//                  'Catalan/Valencia'  => 'Catalan/Valencian',
//                  'Chechen'  => 'Chechen',
                  'Chinese'  => 'Chinese',
//                  'Cornish'  => 'Cornish',
//                  'Corsican'  => 'Corsican',
//                  'Croatian'  => 'Croatian',
//                  'Czech'  => 'Czech',
//                  'Danish'  => 'Danish',
//                  'Dutch'  => 'Dutch',
//                  'Estonian'  => 'Estonian',
//                  'Faroese'  => 'Faroese',
//                  'Fijian'  => 'Fijian',
//                  'Finnish'  => 'Finnish',
                  'French'  => 'French',
//                  'Gaelic/Scottish Gaelic'   => 'Gaelic/Scottish Gaelic',
//                  'Galician'  => 'Galician',
//                  'Georgian'  => 'Georgian',
                  'German'  => 'German',
//                  'Greek'  => 'Greek',
//                  'Gujarati'  => 'Gujarati',
//                  'Haitian/Haitian Creole'   => 'Haitian/Haitian Creole',
//                  'Hebrew'  => 'Hebrew',
//                  'Hindi'  => 'Hindi',
//                  'Hungarian'  => 'Hungarian',
//                  'Icelandic'  => 'Icelandic',
                  'Indonesian'  => 'Indonesian',
//                  'Irish'  => 'Irish',
//                  'Italian'  => 'Italian',
                  'Japanese'  => 'Japanese',
//                  'Javanese'  => 'Javanese',
//                  'Kannada'  => 'Kannada',
//                  'Kazakh'  => 'Kazakh',
//                  'Kirghiz'  => 'Kirghiz',
//                  'Kongo/Kikongo'  => 'Kongo/Kikongo',
                  'Korean'  => 'Korean',
//                  'Kurdish'  => 'Kurdish',
//                  'Lao'  => 'Lao',
//                  'Latvian'  => 'Latvian',
//                  'Limburgish/Limburgic'  => 'Limburgish/Limburgian/Limburgic',
//                  'Lingala'  => 'Lingala',
//                  'Lithuanian'  => 'Lithuanian',
//                  'Luxembourgish'  => 'Luxembourgish',
//                  'Macedonian'  => 'Macedonian',
//                  'Malagasy'  => 'Malagasy',
                  'Malay'  => 'Malay',
//                  'Malayalam'  => 'Malayalam',
//                  'Maltese'  => 'Maltese',
//                  'Manx'  => 'Manx',
//                  'Marathi'  => 'Marathi',
//                  'Moldavian'  => 'Moldavian',
//                  'Mongolian'  => 'Mongolian',
//                  'Nepali'  => 'Nepali',
//                  'Norwegian'  => 'Norwegian',
//                  'Norwegian (Bokmal)'  => 'Norwegian (Bokmal)',
//                  'Norwegian (Nynorsk)'  => 'Norwegian (Nynorsk)',
//                  'Pashto'  => 'Pashto',
//                  'Persian'  => 'Persian',
//                  'Polish'  => 'Polish',
                  'Portuguese'  => 'Portuguese',
//                  'Punjabi'  => 'Punjabi',
//                  'Raeto-Romance'  => 'Raeto-Romance',
//                  'Romani'  => 'Romani',
//                  'Romanian'  => 'Romanian',
                  'Russian'  => 'Russian',
//                  'Sami'  => 'Sami',
//                  'Sardinian'  => 'Sardinian',
//                  'Serbian'  => 'Serbian',
//                  'Sindhi'  => 'Sindhi',
//                  'Slovak'  => 'Slovak',
//                  'Slovenian'  => 'Slovenian',
//                  'Somali'  => 'Somali',
                  'Spanish'  => 'Spanish',
//                  'Swahili'  => 'Swahili',
//                  'Swedish'  => 'Swedish',
//                  'Tahitian'  => 'Tahitian',
//                  'Tajik'  => 'Tajik',
//                  'Tamil'  => 'Tamil',
//                  'Tatar'  => 'Tatar',
//                  'Telugu'  => 'Telugu',
                  'Thai'  => 'Thai',
//                  'Tibetan'  => 'Tibetan',
//                  'Turkish'  => 'Turkish',
//                  'Turkmen'  => 'Turkmen',
//                  'Ukrainian'  => 'Ukrainian',
//                  'Urdu'  => 'Urdu',
//                  'Uzbek'  => 'Uzbek',
                  'Vietnamese'  => 'Vietnamese',
//                  'Welsh'  => 'Welsh',
//                  'Western Frisian'  => 'Western Frisian',
//                  'Yiddish'  => 'Yiddish',
//                  'Yoruba'  => 'Yoruba'
                )
            ),
        ));

    }

}