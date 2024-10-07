import { ReactElement, useState } from 'react';
import 'react-toastify/dist/ReactToastify.css';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/coffee.styled';
import {Coffee} from "../../styles/coffee.styled"; // 'coffee.styled' dosyasını doğru ithal ediyoruz

const Coffe = () => {
  const [selectedImages, setSelectedImages] = useState<File[]>([]);
  const [buttonLoading, setButtonLoading] = useState(false);

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files) {
      const newImages = Array.from(e.target.files);
      setSelectedImages((prevImages) => [...prevImages, ...newImages]);
    }
  };

  const removeImage = (indexToRemove: number) => {
    setSelectedImages((prevImages) =>
        prevImages.filter((_, index) => index !== indexToRemove)
    );
  };

  const submitImages = () => {
    setButtonLoading(true);
    // Görselleri gönderme işlemi burada yapılacak
    console.log('Gönderilen Görseller:', selectedImages);
    setButtonLoading(false);
  };

  return (
      <Styled.Coffee> {/* Styled.Tarot bileşeni doğru kullanıldı */}
        <Card className="card-wrapper">
          <Styled.QuestionForm>
            <p>Kahve falına bakabilmemiz için fincanının en net 6 görüntüsünü yükle, sana özel yorumları al.
              {' '}
              Minimum 4 fotoğraf yükelemelisiniz.
            </p>
            <input
                type="file"
                accept="image/*"
                multiple
                onChange={handleImageChange}
            />
            <div className="image-preview">
              {selectedImages.map((image, index) => (
                  <div key={`image-${index}`} className="image-container">
                    <img
                        src={URL.createObjectURL(image)}
                        alt={`preview-${index}`}
                    />
                    <button
                        type="button"
                        className="remove-image-button"
                        onClick={() => removeImage(index)}
                    >
                      &times;
                    </button>
                  </div>
              ))}
            </div>
            {selectedImages.length >= 4 && (
                <Button
                    className="submit-button"
                    onClick={submitImages}
                    disabled={buttonLoading}
                    loading={buttonLoading}
                >
                  Gönder
                </Button>
            )}
          </Styled.QuestionForm>
        </Card>
      </Styled.Coffee>
  );
};

Coffe.getLayout = (page: ReactElement) => (
    <LoggedInLayout pageName={'COFFE'}>{page}</LoggedInLayout>
);

export default Coffe;
