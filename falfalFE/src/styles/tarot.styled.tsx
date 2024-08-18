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
      &.revert {
        transform: rotate(180deg);
      }
    }
  }

  .tarot-detail-container {
    position: relative;
    padding-top: 140px;
    margin: 0 0 60px;
    .card-showcase {
      position: absolute;
      border-radius: 10px;
      transition: all 0.3s ease-in-out;
      box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
      width: 74px;
      height: 115px;
      &.revert {
        img {
          transform: rotate(180deg);
        }
      }
      &:nth-child(1) {
        transform: rotate(-15deg);
        left: 0;
        bottom: 0;
      }
      &:nth-child(2) {
        transform: rotate(-10deg);
        left: 14%;
        bottom: 10px;
      }
      &:nth-child(3) {
        transform: rotate(-5deg);
        left: 28%;
        bottom: 10px;
      }
      &:nth-child(4) {
        transform: rotate(0deg);
        left: 0;
        right: 0;
        margin: 0 auto;
        bottom: 10px;
      }
      &:nth-child(5) {
        transform: rotate(5deg);
        right: 28%;
        bottom: 10px;
      }
      &:nth-child(6) {
        transform: rotate(10deg);
        right: 14%;
        bottom: 10px;
      }
      &:nth-child(7) {
        transform: rotate(15deg);
        right: 0;
        bottom: 0;
      }
    }
  }
  .submit-button {
    width: 100%;
  }
`;

const QuestionForm = styled.form`
  display: flex;
  flex-direction: column;
  gap: 24px;
`;

export { QuestionForm, Tarot };
