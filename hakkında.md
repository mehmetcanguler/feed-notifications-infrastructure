# Kişiselleştirilmiş Akış ve Bildirim Altyapısı - Teknik Süreç Özeti

Bu proje kapsamında kişiselleştirilmiş akış ve bildirim sistemine temel olacak bir veri toplama ve işleme altyapısı geliştirilmiştir.

## Yapılan Çalışmalar:

- Laravel 12 kullanılarak API tabanlı bir servis yapısı kuruldu.
- Kullanıcı etkileşimleri (click, like, read vs.) için bir endpoint oluşturuldu.
- Gelen her kullanıcı etkileşimi:
  - **Veritabanına** kaydedildi.
  - **Cache (Redis)** içerisine de yedeği alınarak hız optimizasyonu sağlandı.
- Kullanıcı etkileşimleri sisteme kaydedildikten sonra:
  - Kafka’ya veri gönderimi yapıldı (user-interactions topic).
  - Bu veriler ayrıca Redis Stream üzerinde kuyruğa yazıldı (user_interactions_stream).
- Kafka üzerinden gelen verileri dinlemek için bir **Consumer Command** geliştirildi.
- Redis Stream üzerindeki verileri dinlemek ve okumak için **ayrı bir command** geliştirildi.
- Email gönderim altyapısı için Kafka üzerinden **email-send-requests** adında yeni bir konu (topic) kurgulandı.
- Yazılım mimarisinde:
  - DTO (Data Transfer Object) kullanımı ile veri güvenliği ve taşınabilirliği sağlandı.
  - Interface ve Service katmanları kullanılarak bağımlılıklar azaltıldı.
  - Laravel Events, Listener ve Jobs kullanılarak olay tabanlı bir mimari kuruldu.
- Mobil Uygulamalar ile dış bildirim gönderme (Push Notification) için Pushy servisi eklendi (Token bilgisi ile test edilebilir).

---
# Kullanılan Teknolojiler ve Yapılar

## Teknolojiler
- **PHP 8.2**
- **Laravel 12**
- **Docker Compose** (php, mysql, redis, kafka, zookeeper)
- **Redis** (Stream yapısıyla kullanılacak şekilde)
- **Kafka** (Mesajlaşma ve veri kuyruğu için)
- **MySQL** (Veri tabanı)

## Laravel Paketleri
- **mateusjunges/laravel-kafka**: Laravel için Kafka entegrasyonu.
- **knuckleswtf/scribe**:Scribe Api dökümanı oluşturma ve test etme için eklendi

## Uygulanan Tasarım Desenleri
- **DTO (Data Transfer Object)** yapısı: Verilerin güvenli ve net bir şekilde taşınması.
- **Repository-Service Pattern**: Servis katmanı ile kodların modüler ve test edilebilir olması.
- **Event-Listener-Job Yapısı**: Asenkron ve ölçeklenebilir sistem tasarımı.
- **Command Line Scriptler**: Redis ve Kafka üzerinde dinleyici sistemler kurulması.
- **Custom Enum Yapıları**: Topic isimlerinin sabit ve güvenli kullanılması.
- **Redis Stream**: Kullanıcı etkileşim verilerinin yüksek hızlı, sıralı bir şekilde işlenmesi için.

## Diğer Notlar
- Respone ve Hata yönetiminin standartlaştırılması için ApiResponse trait'i yazıldı Exceptions\Handler sınıfı ile Controller içerisinde bir sabit yapı oluşturuldu .
- Kafka için konfig dosyası oluşturuldu.
- Tüm işlemler gerçekçi kullanım senaryoları düşünülerek kurgulanmıştır.
- Kod kalitesi, topluluk standartlarına ve Laravel Best Practice kurallarına dikkat edilerek hazırlanmıştır.
- Gereksiz kod tekrarı, magic string kullanımı, model içinde fazla logic bulundurulması gibi hatalardan özellikle kaçınılmıştır.

# Test Süreci

- Öncelikle **Feature Test** sınıfları hazırlandı ve gerçek kullanıcı senaryolarına uygun testler yazıldı.
- Daha sonra, API uç noktalarını daha gerçekçi bir şekilde test edebilmek için **Scribe** paketi kullanılarak dokümantasyon üretildi.
- Scribe üzerinden oluşturulan interaktif API dokümantasyonu kullanılarak doğrudan HTTP istekleri (route tabanlı testler) gerçekleştirildi.
- Böylece hem kod içerisinden otomatik testler hem de dışarıdan manuel API testleri birlikte yürütüldü.

---
