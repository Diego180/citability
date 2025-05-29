# Imagem base com PHP 8 e Apache
FROM php:8.0-apache

# Instalar extensões necessárias (como PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Copiar o conteúdo da pasta public para a pasta padrão do Apache
COPY public/ /var/www/html/

# Copiar o restante do código para a raiz do container (opcional, para acesso a config e src)
COPY . /var/www/

# Ativar o módulo rewrite do Apache (necessário para rotas amigáveis)
RUN a2enmod rewrite

# Configurar permissões e diretório de trabalho
WORKDIR /var/www/html
