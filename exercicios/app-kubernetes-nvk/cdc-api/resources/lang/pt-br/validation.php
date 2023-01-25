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

    'accepted' => ':attribute deve ser aceito.',
    'active_url' => ':attribute deve ser uma URL válida.',
    'after' => ':attribute deve ser uma data depois de :date.',
    'after_or_equal' => ':attribute deve ser uma data depois ou igual à :date.',
    'alpha' => ':attribute precisa conter apenas letras.',
    'alpha_dash' => ':attribute precisa apenas conter letras, números e dashes.',
    'alpha_num' => ':attribute precisa apenas conter letras e números.',
    'array' => ':attribute deve ser uma lista.',
    'before' => ':attribute deve ser uma data antes de :date.',
    'before_or_equal' => ':attribute deve ser uma data antes ou igual à :date.',
    'between' => [
        'numeric' => ':attribute deve estar entre :min e :max.',
        'file' => ':attribute deve estar entre :min e :max kilobytes.',
        'string' => ':attribute deve estar entre :min e :max caracteres.',
        'array' => ':attribute deve ter entre :min e :max itens.',
    ],
    'boolean' => 'Campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação de :attribute não é igual.',
    'date' => ':attribute não é uma data válida.',
    'date_format' => ':attribute não combina com o formato :format.',
    'different' => ':attribute e :other devem ser diferentes.',
    'digits' => ':attribute deve ter :digits digitos.',
    'digits_between' => ':attribute deve ter entre :min e :max digitos.',
    'dimensions' => ':attribute tem dimensões de imagens inválidas.',
    'distinct' => 'O campo :attribute tem valores duplicados.',
    'email' => ':attribute deve ser um endereço de e-mail válido.',
    'ends_with' => 'O campo :attribute deve terminar com um dos seguintes: :values.',
    'exists' => ':attribute selecionado é inválido.',
    'file' => ':attribute deve ser um arquivo.',
    'filled' => 'O campo :attribute deve ter um valor.',
    'image' => ':attribute deve ser uma imagem.',
    'in' => ':attribute selecionado é inválido.',
    'in_array' => 'O campo :attribute não existe em :other.',
    'integer' => ':attribute deve ser um número inteiro.',
    'ip' => ':attribute deve ser um endereço de IP válido.',
    'ipv4' => ':attribute deve ser um endereço de IPv4 válido.',
    'ipv6' => ':attribute deve ser um endereço de IPv6 válido.',
    'json' => ':attribute deve ser uma string JSON válida.',
    'max' => [
        'numeric' => ':attribute não pode ser maior que :max.',
        'file' => ':attribute não pode ser maior que :max kilobytes.',
        'string' => ':attribute não pode ter mais que :max caracteres.',
        'array' => ':attribute não pode ter mais que :max itens.',
    ],
    'mimes' => ':attribute deve ser um arquivo do tipo: :values.',
    'mimetypes' => ':attribute deve ser um arquivo do tipo: :values.',
    'min' => [
        'numeric' => ':attribute não pode ser menor que :min.',
        'file' => ':attribute não pode ser menor que :min kilobytes.',
        'string' => ':attribute não pode ter menos que :min caracteres.',
        'array' => ':attribute não pode ter menos que :min itens.',
    ],
    'not_in' => ':attribute selecionado é inválido.',
    'numeric' => ':attribute deve ser um número.',
    'password' => 'A senha está incorreta.',
    'present' => 'O campo :attribute deve estar presente.',
    'regex' => 'O formato de :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_unless' => 'O campo :attribute é obrigatório, menos se :other possui :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum desses valores: :values estiverem presentes.',
    'same' => ':attribute e :other devem ser iguais.',
    'size' => [
        'numeric' => ':attribute deve ter :size.',
        'file' => ':attribute deve ter :size kilobytes.',
        'string' => ':attribute deve ter :size caracteres.',
        'array' => ':attribute deve conter :size itens.',
    ],
    'starts_with' => 'O campo :attribute deve começar com um dos seguintes: :values',
    'string' => ':attribute deve ser texto.',
    'timezone' => ':attribute deve ser um zona horária válida.',
    'unique' => ':attribute já está cadastrado com este valor.',
    'uploaded' => ':attribute falhou em dar upload.',
    'url' => 'O formato de :attribute é inválido.',

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

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
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

    'attributes' => [
        'state' => 'estado',
        'acronym' => 'sigla',
        'create_at' => 'criado em',
        'name' => 'nome',
        'state_id' => 'código do estado',
        'id' => 'código',
        'registration' => 'matrícula',
        'mobile' => 'celular',
        'city_id' => 'código da cidade',
        'team_id' => 'código do time',
        'password' => 'senha',
        'password_confirmation' => 'confirmação',
        'approved' => 'aprovado',
        'from' => 'remetente',
        'to' => 'destinatário',
        'title' => 'título',
        'description' => 'descrição',
        'user_id' => 'código do usuário',
        'message_id' => 'código da mensagem',
        'read' => 'lido',
        'role_id' => 'código da permissão',
        'cover' => 'capa',
        'installments_amount' => 'quantidade de parcelas',
        'payment_agreement' => 'autorização',
        'jacket_1_size' => 'tamanho jaqueta 1',
        'jacket_2_size' => 'tamanho jaqueta 2',
    ],
];
