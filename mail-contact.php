<?php
// Bu kod, formun sadece POST metoduyla gönderilip gönderilmediğini kontrol eder.
// Bu, botların ve kötü niyetli kullanıcıların doğrudan bu dosyaya erişmesini engeller.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // E-postanın gönderileceği adresi buraya girin.
    // Daha önceki konuşmalarımızda info@izievents.com.tr adresini konuşmuştuk.
    $giden_email = "info@izievents.com.tr";

    // Formdan gelen verileri alıyoruz.
    // htmlspecialchars() fonksiyonu, güvenlik için zararlı karakterleri temizler.
    // ?? '' ifadesi, bir alan boş gelirse hata vermesini engeller.
    $adsoyad = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $telefon = htmlspecialchars($_POST['phone'] ?? '');
    $hizmet = htmlspecialchars($_POST['service'] ?? '');
    $mesaj = htmlspecialchars($_POST['message'] ?? '');

    // E-postanın konusunu belirliyoruz.
    // Bu sayede gelen e-postanın ne hakkında olduğunu kolayca anlayabilirsin.
    $eposta_konusu = "Web Sitesi İletişim Formu: " . $hizmet;

    // E-posta içeriğini daha düzenli ve okunabilir bir formatta hazırlıyoruz.
    $eposta_icerigi = "Web sitesi üzerinden yeni bir mesaj gönderildi.\n\n";
    $eposta_icerigi .= "Ad Soyad: " . $adsoyad . "\n";
    $eposta_icerigi .= "E-posta: " . $email . "\n";
    $eposta_icerigi .= "Telefon: " . $telefon . "\n";
    $eposta_icerigi .= "Seçilen Hizmet: " . $hizmet . "\n\n";
    $eposta_icerigi .= "Mesaj:\n" . $mesaj . "\n";

    // E-posta başlıkları. Bu, e-postanın kimden geldiğini belirtir.
    // Reply-To başlığı, gelen e-postayı direkt olarak yanıtlayabilmenizi sağlar.
    $headers = "From: " . $adsoyad . " <" . $email . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    // Türkçe karakterlerin doğru görünmesi için charset ayarı.
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // mail() fonksiyonu ile e-postayı gönderiyoruz.
    // Başarılı olup olmadığını kontrol ederek kullanıcıya geri bildirim gönderiyoruz.
    if (mail($giden_email, $eposta_konusu, $eposta_icerigi, $headers)) {
        // Form başarılı bir şekilde gönderilirse bu JSON cevabını döndürür.
        echo json_encode(['status' => 'success', 'message' => 'Mesajınız başarıyla gönderildi. Teşekkür ederiz!']);
    } else {
        // Gönderme sırasında bir hata oluşursa bu JSON cevabını döndürür.
        echo json_encode(['status' => 'error', 'message' => 'Mesajınız gönderilirken bir sorun oluştu. Lütfen tekrar deneyin.']);
    }

} else {
    // Eğer form POST metoduyla gönderilmediyse bu JSON cevabını döndürür.
    // Bu, formun direkt URL'den çağrılmasını engeller.
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek. Form yalnızca POST metoduyla gönderilebilir.']);
}
?>