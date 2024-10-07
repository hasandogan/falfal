import styled from 'styled-components';

const CoffeeDetail = styled.div`
    .back {
        display: inline-block;
        margin-bottom: 20px;
    }
    .card-wrapper {
        padding: 20px;
    }
    .coffee-detail-container {
        display: flex;
        flex-wrap: wrap; // Resimleri yan yana dizmek için
        justify-content: center; // Ortalamak için
        margin: 20px 0;

        .coffee-image {
            width: 100px; // Tüm resimlerin aynı boyutta olması için
            height: 100px; // Yükseklik
            object-fit: cover; // Resimleri kesmeden kutuya sığdırmak için
            margin: 5px; // Resimler arasında boşluk
        }
    }
    .coffee-message {
        font-size: 15px;
        line-height: 18px;
        color: var(--white);
    }
`;

export { CoffeeDetail };
