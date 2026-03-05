<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => ':attribute duhet pranuar.',
    'active_url'      => ':attribute nuk është një adresë interneti e vlefshme.',
    'after'           => ':attribute duhet të jetë një datë pas datës :date.',
    'after_or_equal'  => ':attribute duhet të jetë një datë pas datës :date ose e barabartë me datën :date.',
    'alpha'           => ':attribute mund të përbëhet vetëm me shkronja.',
    'alpha_dash'      => ':attribute mund të përbëhet vetëm me shkronja, numra, viza dhe vija nënvizore.',
    'alpha_num'       => ':attribute mund të përbëhet vetëm me shkronja dhe numra.',
    'array'           => ':attribute duhet të jetë një grup.',
    'before'          => ':attribute duhet të jetë një datë para datës :date.',
    'before_or_equal' => ':attribute duhet të jetë një datë para datës :date ose e barabartë me datën :date.',
    'between'         => [
        'numeric' => ':attribute duhet të jetë midis :min & :max.',
        'file'    => ':attribute duhet të jetë midis :min & :max kilobajt në madhësi.',
        'string'  => ':attribute duhet të jetë nga :min deri në :max karaktere.',
        'array'   => ':attribute duhet të ketë midis :min & :max elemente.',
    ],
    'boolean'        => ":attribute duhet të jetë ose 'e vërtetë' ose 'jo e vërtetë'.",
    'confirmed'      => ':attribute nuk përputhet me konfirmimin.',
    'date'           => ':attribute duhet të jetë një datë e vlefshme.',
    'date_equals'    => ':attribute duhet të jetë një datë e barabartë me :date.',
    'date_format'    => ':attribute nuk përputhet me formatin e vlefshëm për :format.',
    'different'      => ':attribute dhe :other duhet të jetë i ndryshëm.',
    'digits'         => ':attribute duhet të ketë shifra :digits.',
    'digits_between' => ':attribute duhet të ketë nga :min deri në :max shifra.',
    'dimensions'     => ':attribute ka dimensione të pavlefshme imazhi.',
    'distinct'       => ':attribute përmban një vlerë ekzistuese.',
    'email'          => ':attribute Duhet të jetë një e-mail adresë e vlefshme.',
    'ends_with'      => ':attribute duhet të ketë një nga mbaresat e mëposhtme: :values',
    'exists'         => 'Vlera e zgjedhur për :attribute është e pavlefshme.',
    'file'           => ':attribute duhet të jetë një skedar.',
    'filled'         => ':attribute duhet të plotësohet.',
    'gt'             => [
        'numeric' => ':attribute duhet të jetë më i madh se :value.',
        'file'    => ':attribute duhet të jetë më i madh se :value kilobajt.',
        'string'  => ':attribute duhet të jetë më i gjatë se :value karaktere.',
        'array'   => ':attribute duhet të ketë më shumë se :value elemente.',
    ],
    'gte' => [
        'numeric' => ':attribute duhet të jetë më i madh ose i barabartë me :value.',
        'file'    => ':attribute duhet të jetë më i madh ose i barabartë me :value kilobajt.',
        'string'  => ':attribute duhet të jetë së paku :value karaktere i gjatë.',
        'array'   => ':attribute duhet të ketë të paktën :value elemente.',
    ],
    'image'    => ':attribute duhet të jetë një foto.',
    'in'       => 'Vlera e zgjedhur për :attribute është e pavlefshme.',
    'in_array' => 'Vlera e zgjedhur për :attribute nuk shfaqet në :other.',
    'integer'  => ':attribute duhet të jetë një numër i plotë.',
    'ip'       => ':attribute duhet të jetë një adresë IP e vlefshme.',
    'ipv4'     => ':attribute duhet të jetë një adresë e vlefshme IPv4.',
    'ipv6'     => ':attribute duhet të jetë një adresë e vlefshme IPv6.',
    'json'     => ':attribute duhet të jetë një varg i vlefshëm JSON.',
    'lt'       => [
        'numeric' => ':attribute duhet të jetë më pak se :value.',
        'file'    => ':attribute duhet të jetë më pak se :value kilobajt.',
        'string'  => ':attribute duhet të jetë më i shkurtër se :value karaktere.',
        'array'   => ':attribute duhet të ketë më pak se :value elemente.',
    ],
    'lte' => [
        'numeric' => ':attribute duhet të jetë më i vogël ose i barabartë me :value.',
        'file'    => ':attribute duhet të jetë më i vogël ose i barabartë me :value kilobajt.',
        'string'  => ':attribute mund të jetë maksimum :value karaktere.',
        'array'   => ':attribute mund të ketë maksimum :value elemente.',
    ],
    'max' => [
        'numeric' => ':attribute mund të jetë maksimumi :max.',
        'file'    => ':attribute mund të jetë maksimumi :max kilobajt në madhësi.',
        'string'  => ':attribute mund të ketë maksimumi :max karaktere.',
        'array'   => ':attribute mund të ketë maksimumi :max elemente.',
    ],
    'mimes'     => ':attribute duhet të jetë i llojit të skedarit :values.',
    'mimetypes' => ':attribute duhet të jetë i llojit të skedarit :values.',
    'min'       => [
        'numeric' => ':attribute duhet të jetë së paku :min.',
        'file'    => ':attribute duhet të jetë së paku :min kilobajt në madhësi.',
        'string'  => ':attribute duhet të jetë së paku :min karaktere të gjata.',
        'array'   => ':attribute duhet të ketë të paktën :min elemente.',
    ],
    'not_in'               => 'Vlera e zgjedhur për :attribute është e pavlefshme.',
    'not_regex'            => ':attribute ka një format të pavlefshëm.',
    'numeric'              => ':attribute duhet të jetë një numër.',
    'password'             => 'Fjalëkalimi është i gabuar.',
    'present'              => ':attribute duhet të jetë i pranishëm.',
    'regex'                => ':attribute Formati është i pavlefshëm.',
    'required'             => ':attribute duhet të plotësohet.',
    'required_if'          => ':attribute duhet të plotësohet nëse :other ka vlerën :value.',
    'required_unless'      => ':attribute duhet të plotësohet nëse :other nuk ka vlerën :values.',
    'required_with'        => ':attribute duhet të plotësohet nëse :values është plotësuar.',
    'required_with_all'    => ':attribute duhet të plotësohet nëse :values është plotësuar.',
    'required_without'     => ':attribute duhet të plotësohet nëse :values nuk është plotësuar.',
    'required_without_all' => ':attribute asnjë nga fushat :values nuk u plotësuan.',
    'same'                 => ':attribute dhe :other duhet të përputhen.',
    'size'                 => [
        'numeric' => ':attribute duhet të jetë e barabartë me :size.',
        'file'    => ':attribute duhet të jetë :size kilobajt në madhësi.',
        'string'  => ':attribute duhet të jetë i gjatë :size karaktere.',
        'array'   => ':attribute duhet të ketë saktësisht :size elemente.',
    ],
    'starts_with' => ':attribute duhet të fillojë me një nga sa vijon: :values',
    'string'      => ':attribute duhet të jetë një varg.',
    'timezone'    => ':attribute duhet të jetë një zonë kohore e vlefshme.',
    'unique'      => ':attribute është marrë tashmë.',
    'uploaded'    => ':attribute nuk mund të ngarkohej.',
    'url'         => ':attribute duhet të jetë një url.',
    'uuid'        => ':attribute duhet të jetë një UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    /*'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],*/
      'custom' => [
        'phoneNr' => [
            'required' => 'Numri i celularit duhet të plotësohet.',
            'max'  => 'Numri i telefonit celular mund të ketë maksimumi :max karaktere.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
