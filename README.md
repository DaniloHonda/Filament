# 📌 Curso Laravel/Filament

## 🚀 Instalação do Repositório  

Siga os passos abaixo para configurar o projeto em sua máquina:  

### 1️⃣ Clonar o repositório  
```bash
git clone https://github.com/DaniloHonda/Filament.git
cd Filament
```

2️⃣ Instalar as dependências
```bash
composer install
```

3️⃣ Configurar o banco de dados
Criar um banco de dados filament no PostgreSQL.
Criar o arquivo .env baseado no .env.example e atualizar as seguintes variáveis:
```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=filament
DB_USERNAME=postgres
DB_PASSWORD=suasenha
```
Ajuste as demais variáveis DB_* conforme a configuração do seu banco de dados local.

4️⃣ Gerar a chave da aplicação
```bash
php artisan key:generate
```

5️⃣ Gerar a chave da aplicação
```bash
php artisan migrate --seed
```

6️⃣ Subir a aplicação localmente
```bash
php artisan serve
```

Agora, acesse o projeto pelo navegador e pronto! 🚀
