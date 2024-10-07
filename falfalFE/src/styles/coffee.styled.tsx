import styled from 'styled-components';

export const Coffee = styled.div`
  .card-wrapper {
    padding: 20px;
  }

  .image-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px; /* Görseller arasında boşluk */
    justify-content: flex-start;
  }

  .image-container {
    position: relative;
    width: 100px;
    height: 150px;
  }

  .image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Görsellerin taşmaması için */
    border-radius: 8px;
  }

  .remove-image-button {
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    padding: 2px 6px;
    font-size: 14px;
  }

  @media (min-width: 600px) {
    .image-container {
      flex: 1 1 calc(33.33% - 10px);
    }
  }
`;

export const QuestionForm = styled.form`
  display: flex;
  flex-direction: column;
  gap: 20px;
`;
