import styled from 'styled-components';

const Tarot = styled.div`
  .selected-cards {
    min-height: 100px;
  }
  .tarot-container {
    position: relative;
    width: calc(100vw - 100px);
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .card-wrapper {
    padding: 50px;
  }
  .selected-cards {
    margin: 80px auto 20px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    .card-showcase {
      min-height: 100px;
      padding: 10px;
      background-repeat: no-repeat;
      background-image: url('/images/cards/1.jpg');
      background-size: contain;
    }
  }
`;

const QuestionForm = styled.form`
  display: flex;
  flex-direction: column;
  gap: 24px;
`;

export { QuestionForm, Tarot };
