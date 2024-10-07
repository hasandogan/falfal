import { ReactElement } from 'react';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/coffee.styled';
import CoffeeLogic from '../../hooks/Coffee.logic'; // Mantık dosyasını ithal ediyoruz

const CoffeeUpload = () => {
  const {
    fileInputRef,
    handleImageChange,
    removeImage,
    submitCoffee,
    buttonLoading,
    images,
    handleButtonClick,
  } = CoffeeLogic(); // Mantık fonksiyonunu kullanıyoruz

  return (
      <Styled.Coffee>
        <Card className="card-wrapper">
          <Styled.QuestionForm>
            <p>Kahve falına bakabilmemiz için fincanının en net 6 görüntüsünü yükle</p>

            {/* Gizli input ve stilize edilmiş buton */}
            <input
                type="file"
                accept="image/*"
                multiple
                ref={fileInputRef}
                onChange={handleImageChange}
                style={{ display: 'none' }} // input'u gizliyoruz
            />
            <Button type="button" onClick={handleButtonClick}>
              Dosyaları Seç
            </Button>

            <div className="image-preview">
              {images.map((image, index) => (
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

            {/* 4 ve üstü resim yüklendiğinde buton aktif olacak */}
            {images.length >= 4 && (
                <Button
                    className="submit-button"
                    onClick={submitCoffee}
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

CoffeeUpload.getLayout = (page: ReactElement) => (
    <LoggedInLayout pageName={'COFFEE'}>{page}</LoggedInLayout>
);

export default CoffeeUpload;
