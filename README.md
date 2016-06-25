# Rastreamento Correios
Rastreamento de objetos pelo código.

## Instruções

### Instalação
Execute no terminal:
```
composer require "adrpnt/tracker-correios":"dev-master"
```

Ou edite seu composer.json e adicione
```
require : {
    "adrpnt/tracker-correios": "dev-master"
}
```

### Exemplo
```
require 'vendor/autoload.php';

use Correios\Tracker;

$tracking = new Tracker();
$tracking->setUser('ECT')
    ->setPassword('SRO')
    ->setType('L')
    ->setResult('T')
    ->setObjects('SQ458226057BR'); // ou se forem mais de um código = array('SQ458226057BR', 'RA132678652BR')

$result = $tracking->track();
```

### Resultado
O resultado sempre será um objeto, com os seguintes atributos:  
versao, qtd e objeto

### Observações
Apenas os valores L ou F são suportados para o atributo TYPE.  
Apenas os valores T ou U são suportados para o atributo RESULT.
