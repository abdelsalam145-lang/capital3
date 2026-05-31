# دليل التشغيل — نظام عاصمة الكون للمصاعد

موجّه لمبرمج / فريق تقني لديه خبرة بـ Laravel. الهدف: تشغيل النظام محلياً وتجربته خلال دقائق.

## المتطلبات

- PHP 8.2 أو أحدث
- Composer
- PostgreSQL 14+ (أو عدّل `DB_CONNECTION=mysql` في `.env` لاستخدام MySQL)
- (اختياري) Node.js إن أردت تشغيل واجهة الويب React

## خطوات التشغIL (محلياً)

```bash
# 1) فك ضغط المشروع والدخول لمجلده
cd elevator

# 2) تثبيت اعتماديات Laravel (يجلب إطار العمل وإعداداته الافتراضية)
composer install

# 3) تجهيز ملف البيئة
cp .env.example .env
php artisan key:generate

# 4) إنشاء قاعدة بيانات فارغة باسم elevators في PostgreSQL، ثم اضبط بياناتها في .env:
#    DB_DATABASE=elevators
#    DB_USERNAME=postgres
#    DB_PASSWORD=كلمة_المرور

# 5) تشغيل الهجرات (إنشاء كل الجداول)
php artisan migrate

# 6) تعبئة بيانات تجريبية (مستخدمون + عملاء + مصاعد + طلبات)
php artisan db:seed

# 7) تشغيل الخادم
php artisan serve
```

النظام الآن يعمل على: `http://localhost:8000`
وواجهة الـ API على: `http://localhost:8000/api`

## حسابات الدخول التجريبية

كلمة المرور للجميع: `password`

| الدور | البريد |
|------|--------|
| مدير | admin@asimat.com |
| مشرف | super1@asimat.com (وكذلك super2 و super3) |
| فني | tech1@asimat.com (حتى tech4) |
| عميل | customer@asimat.com |

## تجربة الـ API بسرعة

```bash
# 1) تسجيل الدخول للحصول على رمز
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@asimat.com","password":"password"}'

# الرد يحتوي "token": "..."  — انسخه

# 2) جلب لوحة المؤشرات بالرمز
curl http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer الـTOKEN_هنا"

# 3) جلب الطلبات
curl http://localhost:8000/api/requests \
  -H "Authorization: Bearer الـTOKEN_هنا"
```

أو استخدم Postman / Insomnia: ضع الرمز في خانة Bearer Token وجرّب المسارات.

## واجهة لوحة المؤشرات (React)

ملف `dashboard-ui.jsx` جاهز. لتشغيله ضمن مشروع React/Vite:

```bash
npm create vite@latest dashboard -- --template react
cd dashboard && npm install recharts lucide-react
# انسخ dashboard-ui.jsx إلى src/ واستوردها في App
npm run dev
```

داخل الملف، استبدل ثابت `SAMPLE` بنداء `fetch("http://localhost:8000/api/dashboard")` مع ترويسة الرمز.

## خريطة المسارات الكاملة

كل المسارات في `routes/api.php`. أهمها:
- `POST /api/login` — الدخول
- `GET /api/dashboard` — لوحة المؤشرات (مدير/مشرف)
- `GET|POST /api/requests` — الطلبات
- `POST /api/requests/{id}/assign` — تعيين فني
- `GET|POST /api/contracts` — العقود
- `GET|POST /api/invoices` — الفواتير
- `POST /api/invoices/{id}/pay-online` — الدفع الإلكتروني
- `GET|POST /api/parts` — المخزون
- `POST /api/requests/{id}/parts` — استهلاك قطعة في طلب

## ملاحظات مهمة

1. **بوابة الدفع:** ضع مفاتيح Moyasar في `.env` لتفعيل الدفع الإلكتروني. بدونها تعمل بقية الميزات والدفع اليدوي بشكل طبيعي.

2. **الجدولة:** أمر `invoices:process` (فواتير متأخرة + دورية) مجدول في `routes/console.php`. لتفعيله فعلياً أضِف cron على الخادم:
   ```
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```

3. **MySQL بدل PostgreSQL:** يعمل، لكن استعلامات التقارير في `DashboardService` تستخدم `to_char(...)` الخاص بـ PostgreSQL. عند استخدام MySQL استبدلها بـ `DATE_FORMAT(...)`.

4. **الأمان قبل النشر الفعلي:** هذه نسخة تطوير. قبل النشر للإنتاج راجع: تفعيل HTTPS، ضبط `APP_DEBUG=false`، إضافة Policies للصلاحيات على مستوى أدق، وكتابة اختبارات. هذه خطوات بنينا أساسها وننصح بإكمالها قبل التشغيل الحقيقي مع عملاء فعليين.
```
