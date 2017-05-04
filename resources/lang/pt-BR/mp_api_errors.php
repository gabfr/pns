<?php

return [

	// Refering to this docs: https://www.mercadopago.com.br/developers/pt/api-docs/custom-checkout/customers-cards/
    'customers' => [

    	'http_400' => [
    		'error_100' => 'As credenciais são obrigatórias',
    		'error_101' => 'Esse cliente já está cadastrado',
    		'error_102' => 'O id do cliente (customer id) não está na requisição',
    		'error_103' => 'O parâmetro deve ser um objeto',
    		'error_104' => 'O parâmetro é grande demais',
    		'error_105' => 'O id do cliente (customer id) é inválido',
    		'error_106' => 'O e-mail informado é inválido',
    		'error_107' => 'O primeiro nome é inválido',
    		'error_108' => 'O sobrenome é inválido',
    		'error_109' => 'O código da área do telefone é inválido',
    		'error_110' => 'O número do telefone é inválido',
    		'error_111' => 'O tipo de documento de identificação é inválido',
    		'error_112' => 'O número do documento de identificação é inválido',
    		'error_113' => 'O CEP é inválido',
    		'error_114' => 'O endereço é inválido',
    		'error_115' => 'A data de registro é inválida',
    		'error_116' => 'A descrição é inválida',
    		'error_117' => 'Os metadados (metadata) não são válidos',
    		'error_118' => 'O corpo da requisição deve ser um objeto JSON',
    		'error_119' => 'As informações do cartão são obrigatórias',
    		'error_120' => 'Cartão não encontrado',
    		'error_121' => 'O cartão é inválido',
    		'error_122' => 'Os dados do cartão são inválidos',
    		'error_123' => 'O código do método de pagamento (payment_method_id) é obrigatório',
    		'error_124' => 'O identificador do emissor (issuer_id) é obrigatório',
    		'error_125' => 'Parâmetros inválidos',
    		'error_126' => 'Parâmetro inválido. Não é possível alterar o e-mail',
    		'error_127' => 'Parâmetro inválido. Não foi possível acessar o meio de pagamento do cartão, verifique o identificador do emissor (issuer_id) e o método de pagamento (payment_method_id)',
    		'error_128' => 'O formato do e-mail é inválido',
    		'error_129' => 'O cliente atingiu o número máximo de cartões registrados',

    		'error_140' => 'O dono do cartão especificado não é válido',

    		'error_150' => 'Os usuários envolvidos são inválidos',

    		'error_200' => 'O formato do período (range) é inválido (período=:date_parameter:after::date_from,before::date_to)',
    		'error_201' => 'O atributo de período (range) deve pertencer a uma entidade do tipo data',
    		'error_202' => 'Parâmetro \'after\' do período é inválido. Deve ser uma data (formato iso_8601)',
    		'error_203' => 'Parâmetro \'before\' do período é inválido. Deve ser uma data (formato iso_8601)',
    		'error_204' => 'Formato dos filtros não é válido',
    		'error_205' => 'Formato da consulta não é válido',
    		'error_206' => 'Os atributos de ordenação devem ser atributos pertencentes ao objeto do cliente (customer)',
    		'error_207' => 'O sentido da ordenação (order filter) deve ser ascendente ou decrescente (asc ou desc)',
    		'error_208' => 'Parâmetro ordenação não está em um formato válido',
    	],

    	'http_401' => 'Acesso não autorizado. Verifique suas credenciais.',

    	'http_404' => 'Recurso não encontrado'
    ]

];