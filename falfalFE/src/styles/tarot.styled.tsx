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
    margin-bottom: 80px;
  }
  .selected-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 80px;
    .card-showcase {
      min-height: 100px;
      padding: 10px;
      background-repeat: no-repeat;
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
