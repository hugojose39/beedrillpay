# Beedrillpay: Cielo

**Biblioteca de Processamento de Pagamentos Cielo**

![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)

## Instalar

Via Composer

``` bash
$ composer require hugojose/beedrillpay
```

## Uso Básico

No topo da classe que irá usar a biblioteca será necessário configurar o seguinte require:

``` php
require __DIR__.'/vendor/autoload.php';
```

O seguinte gateway é fornecido por este pacote:

 * [Cielo](https://cielo.com.br/)

### Exemplo de Tokenização de cartão
``` php
    // Configuração do service provider para configurar as chaves necessárias e o modo selecionado, então como terceiro parametro da função configure passe false se estiver utilizando a biblioteca em produção.

    $gateway = BeedrillpayServiceProvider::configure('MerchantId', 'MerchantKey', true);

    $gateway->cardToken([
        'Card' => [
            'CustomerName' => 'Comprador Teste Cielo',
            'CardNumber' => '4532117080573700',
            'Holder' => 'Comprador T Cielo',
            'ExpirationDate' => '12/2030',
            'Brand' => 'Visa',
        ],
    ]);

    //Em caso de sucesso o token do cartão será fornecido como resposta da api da cielo, em caso de falha uma exceção será retoranada.
```

### Exemplo de Criação de cobrança capturada automaticamente
``` php
    // Configuração do service provider para configurar as chaves necessárias e o modo selecionado, então como terceiro parametro da função configure passe false se estiver utilizando a biblioteca em produção.

    $gateway = BeedrillpayServiceProvider::configure('MerchantId', 'MerchantKey', true);

    // Exemplo de cobrança via boleto

    $gateway->automaticCapture([
        'MerchantOrderId' => '2014111706',
        'Customer' => [
            'Name' => 'Comprador Teste Boleto',
            'Identity' => '1234567890',
            'Address' => [
                'Street' => 'Avenida Marechal Câmara',
                'Number' => '160',
                'Complement' => 'Sala 934',
                'ZipCode' => '22750012',
                'District' => 'Centro',
                'City' => 'Rio de Janeiro',
                'State' => 'RJ',
                'Country' => 'BRA'
            ],
        ],
        'Payment' => [
            'Type' => 'Boleto',
            'Amount' => 15700,
            'Provider' => 'bradesco2',
            'Address' => 'Rua Teste',
            'BoletoNumber' => '123',
            'Assignor' => 'Empresa Teste',
            'Demonstrative' => 'Desmonstrative Teste',
            'ExpirationDate' => '5/1/2024',
            'Identification' => '11884926754',
            'Instructions' => 'Aceitar somente até a data de vencimento, após essa data juros de 1% dia.'
        ],
    ]);

    //Em caso de sucesso o token do cartão será fornecido como resposta da api da cielo, em caso de falha uma exceção será retoranada.
```

### Exemplo de Criação de cobrança para ser capturada
``` php
    // Configuração do service provider para configurar as chaves necessárias e o modo selecionado, então como terceiro parametro da função configure passe false se estiver utilizando a biblioteca em produção.

    $gateway = BeedrillpayServiceProvider::configure('MerchantId', 'MerchantKey', true);

    // Exemplo de cobrança via Cartão de crédito

    $gateway->laterCapture([
        'MerchantOrderId' => '2014111706',
        'Customer' => [
            'Name' => 'Comprador Teste Boleto',
            'Identity' => '1234567890',
            'Address' => [
                'Street' => 'Avenida Marechal Câmara',
                'Number' => '160',
                'Complement' => 'Sala 934',
                'ZipCode' => '22750012',
                'District' => 'Centro',
                'City' => 'Rio de Janeiro',
                'State' => 'RJ',
                'Country' => 'BRA'
            ],
        ],
        'Payment' => [
            'Type' =>'CreitCard',
            'Amount' =>15700,
            'Installments' =>1,
            'SoftDescriptor' =>'123456789ABCD',
            'CreditCard' => [
                'CardNumber' =>'4551870000000183',
                'Holder' =>'Teste Holder',
                'ExpirationDate' =>'12/2021',
                'SecurityCode' =>'123',
                'Brand' =>'Visa'
            ],
        ],
    ]);

    //Em caso de sucesso o token do cartão será fornecido como resposta da api da cielo, em caso de falha uma exceção será retoranada.
```

### Exemplo de Capturar Cobrança
``` php
    // Configuração do service provider para configurar as chaves necessárias e o modo selecionado, então como terceiro parametro da função configure passe false se estiver utilizando a biblioteca em produção.

    $gateway = BeedrillpayServiceProvider::configure('MerchantId', 'MerchantKey', true);

    $gateway->capture([
        'Amount' => 1000 // Este valor deve ser em centavo e inteiro.
        'PaymentId' => '1234'
    ]);

    //Em caso de sucesso o token do cartão será fornecido como resposta da api da cielo, em caso de falha uma exceção será retoranada.
```

## Licença

Este projeto é licenciado sob a Licença MIT.