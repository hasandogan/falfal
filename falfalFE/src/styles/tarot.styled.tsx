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
`;

const QuestionForm = styled.form`
  display: flex;
  flex-direction: column;
  gap: 24px;
`;

export { QuestionForm, Tarot };
