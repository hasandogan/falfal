import { useRouter } from 'next/router';
import { useRef, useState } from 'react';
import { toast } from 'react-toastify';
import { sendCoffee } from '../services/coffee/send-coffee';

const CoffeeLogic = () => {
  const router = useRouter();
  const [buttonLoading, setButtonLoading] = useState<boolean>(false);
  const [images, setImages] = useState<File[]>([]); // Resimler state'de saklanacak
  const fileInputRef = useRef<HTMLInputElement | null>(null); // Dosya girişi referansı

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      const newImages = Array.from(e.target.files);
      setImages((prevImages) => [...prevImages, ...newImages]); // Seçilen resimleri ekliyoruz
    }
  };

  const removeImage = (indexToRemove: number) => {
    setImages((prevImages) =>
        prevImages.filter((_, index) => index !== indexToRemove) // Resimleri kaldırıyoruz
    );
  };

  const resizeImage = (file: File, maxWidth: number, maxHeight: number): Promise<string> => {
    return new Promise((resolve, reject) => {
      const img = new Image();
      const reader = new FileReader();

      reader.onload = (e) => {
        if (e.target && e.target.result) {
          img.src = e.target.result as string;
        }
      };

      reader.readAsDataURL(file);

      img.onload = () => {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        let width = img.width;
        let height = img.height;

        // Aspect ratio koruma
        if (width > height) {
          if (width > maxWidth) {
            height *= maxWidth / width;
            width = maxWidth;
          }
        } else {
          if (height > maxHeight) {
            width *= maxHeight / height;
            height = maxHeight;
          }
        }

        canvas.width = width;
        canvas.height = height;
        ctx?.drawImage(img, 0, 0, width, height);
        resolve(canvas.toDataURL('image/jpeg', 0.7)); // JPEG formatında ve %70 kalite ile dönüştür
      };

      img.onerror = (error) => {
        reject(error);
      };
    });
  };


  const submitCoffee = async () => {
    setButtonLoading(true);

    // Base64 formatındaki resimleri tutacak dizi
    const base64Images: string[] = [];

    // Resimlerin Base64'e dönüştürülmesi ve boyutlandırılması
    const promises = images.map((image) => {
      return resizeImage(image, 800, 600); // 800x600 piksel boyutlandır
    });

    try {
      // Tüm resimleri dönüştürme işlemi
      base64Images.push(...(await Promise.all(promises)));

      const request = {
        images: base64Images, // Boyutlandırılmış ve Base64 formatında resimler
      };

      console.log('request', request);
      const response = await sendCoffee(request); // Resim gönderme işlemi
      console.log('response', response);
      toast.success(response?.message || 'Resimler gönderildi');
      router.push('/home');
    } catch (error: any) {
      console.log('error', error);
      toast.error(error?.response?.data?.message || 'Bir problem oluştu');
    }

    setButtonLoading(false);
  };



  const handleButtonClick = () => {
    fileInputRef.current?.click(); // Dosya seçme işlemi buton ile tetikleniyor
  };

  return {
    fileInputRef,
    handleImageChange,
    removeImage,
    submitCoffee,
    buttonLoading,
    images, // Resimleri dışa aktarıyoruz
    handleButtonClick,
  };
};

export default CoffeeLogic;
