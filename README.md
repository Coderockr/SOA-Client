Introdução
===========

Essa é uma aplicação de modelo a ser usada como base para a criação de aplicativos baseados no [Coderockr SOA-Server](http://github.com/Coderockr/SOA-Server)


Estrutura dos diretórios
------------------------

- bin: diretório de executáveis do projeto, como o doctrine
- configs
    - clients.php: identificação dos clientes que podem acessar os dados via REST/RPC
    - configs.php: configurações de conexão com banco de dados entre outras
- library: diretórios da aplicação
    - model: entidades a serem acessadas via REST
    - service: serviços a serem acessadas via RPC
    - test: testes unitários das entidades e serviços
- logs: logs de erros gerados pelo SOA-Server
- vendor: diretório de dependências externas
- vendor.sh: shell script que faz a instalação das dependências externas
- bootstrap.php: configurações necessárias para a aplicação, como inicialização do Doctrine
- phpunit.xml: arquivo usado pelo PHPUnit

Instalação
----------

- Clonar o projeto
- Executar o vendors.sh
- Duplicar o arquivo configs/configs.php para configs/configs.development.php e fazer as alterações necessárias
- Duplicar o arquivo configs/configs.php para configs/configs.testing.php e fazer as alterações necessárias
- Criar o domínio virtual do Apache para usar o SOA-Server, conforme o exemplo (alterando o ServerName, DocumentRoot e APPLICATION_PATH ):

```
<VirtualHost *:80>
   	    DocumentRoot "/vagrant/SOA-Server"
		ServerName soasample.dev
        SetEnv APPLICATION_ENV development
        SetEnv APPLICATION_PATH /vagrant/SOA-Client
        Header set Access-Control-Allow-Origin *

        <Directory "/vagrant/SOA-Server">
                Options Indexes Multiviews FollowSymLinks
                AllowOverride All
                Order allow,deny
                Allow from all

                <Limit GET HEAD POST PUT DELETE OPTIONS>
                        Order Allow,Deny
                        Allow from all
                </Limit>

                RewriteEngine On
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteRule !\.(js|ico|gif|jpg|png|css|htm|html|txt|mp3)$ index.php
                RewriteRule .? - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
        </Directory>
</VirtualHost>
```
- Configurar /etc/hosts:
    
    127.0.0.1   soasample.dev

Geração das tabelas
-------------------

Para que o Doctrine gere as tabelas no banco de dados baseado nas entidades:

	APPLICATION_ENV=development php ./bin/doctrine.php orm:schema-tool:create
	APPLICATION_ENV=testing php ./bin/doctrine.php orm:schema-tool:create
	
Desta forma são criadas as tabelas na base de desenvolvimento e na de testes