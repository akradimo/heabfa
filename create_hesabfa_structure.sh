#!/bin/bash  

# نام دایرکتوری اصلی پروژه  
PROJECT_NAME="hesabfa"  

# ایجاد دایرکتوری اصلی  
mkdir -p "$PROJECT_NAME"  
cd "$PROJECT_NAME"  

# ایجاد دایرکتوری app  
mkdir -p app/Core/Domain/Models  
mkdir -p app/Core/Domain/Repositories  
mkdir -p app/Core/Domain/Services  
mkdir -p app/Core/Domain/Interfaces  
mkdir -p app/Core/Application/DTOs  
mkdir -p app/Core/Application/Services  
mkdir -p app/Core/Application/Validators  
mkdir -p app/Core/Infrastructure/Database  
mkdir -p app/Core/Infrastructure/Cache  
mkdir -p app/Core/Infrastructure/Queue  
mkdir -p app/Http/Controllers/Auth  
mkdir -p app/Http/Controllers/Accounting  
mkdir -p app/Http/Controllers/Reports  
mkdir -p app/Http/Controllers/Settings  
mkdir -p app/Http/Middleware  
mkdir -p app/Http/Requests  
mkdir -p app/Providers  

# ایجاد دایرکتوری config  
mkdir -p config  

# ایجاد فایل‌های config (می‌تونید فایل‌های خالی ایجاد کنید یا محتوای پیش‌فرض رو هم بذارید)  
touch config/app.php  
touch config/auth.php  
touch config/database.php  
touch config/hesabfa.php  

# ایجاد دایرکتوری database  
mkdir -p database/migrations  
mkdir -p database/seeders  
mkdir -p database/factories  

# ایجاد دایرکتوری resources  
mkdir -p resources/js/components/accounting  
mkdir -p resources/js/components/reports  
mkdir -p resources/js/components/settings  
mkdir -p resources/js/store/modules  
touch resources/js/store/index.js  
mkdir -p resources/js/router  
touch resources/js/App.vue  
mkdir -p resources/sass/components  
touch resources/sass/app.scss  
mkdir -p resources/views/layouts  
mkdir -p resources/views/pages  

# ایجاد دایرکتوری routes  
mkdir -p routes  

# ایجاد فایل‌های routes (می‌تونید فایل‌های خالی ایجاد کنید یا محتوای پیش‌فرض رو هم بذارید)  
touch routes/api.php  
touch routes/web.php  

# ایجاد دایرکتوری storage  
mkdir -p storage/app/public  
mkdir -p storage/framework  
mkdir -p storage/logs  

# ایجاد دایرکتوری tests  
mkdir -p tests/Unit  
mkdir -p tests/Feature  

# ایجاد فایل‌های اصلی پروژه (فایل‌های خالی ایجاد میشن)  
touch .env  
touch .gitignore  
touch composer.json  
touch package.json  
touch README.md  

echo "ساختار دایرکتوری hesabfa با موفقیت ایجاد شد."  