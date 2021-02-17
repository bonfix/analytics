<?php

return [
	'table_prefix' => 'analytics_',
	'polymorph_bindings' => [
		'product' => 'App\Models\Items\Item',
		'beneficiary' => 'App\Models\Users\Beneficiary',
	],

];