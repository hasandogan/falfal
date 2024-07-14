# Proje ilk ayağa kaldırıldıktan sonra yapılacaklar
- docker exec -it falfal_webserver bash
- php bin/console d:s:u --force
- php bin/console lexik:jwt:generate-token