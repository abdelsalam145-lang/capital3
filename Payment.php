APP_NAME="Asimat AlKawn Elevators"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Riyadh
APP_URL=http://localhost:8000
APP_LOCALE=ar

LOG_CHANNEL=stack
LOG_LEVEL=debug

# قاعدة البيانات PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=elevators
DB_USERNAME=postgres
DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

# البريد (للإشعارات) - اضبطه لاحقاً
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@asimat-alkawn.com"
MAIL_FROM_NAME="${APP_NAME}"

# Sanctum (للمصادقة في الويب والجوال)
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1
FRONTEND_URL=http://localhost:3000

# بوابة الدفع Moyasar
MOYASAR_SECRET_KEY=
MOYASAR_PUBLISHABLE_KEY=
