import styled from 'styled-components';

const TarotDetail = styled.div`
  .back {
    display: inline-block;
    margin-bottom: 20px;
  }
  .card-wrapper {
    padding: 20px;
  }
  .tarot-detail-container {
    position: relative;
    padding-top: 140px;
    margin: 0 0 40px;
    .result-card {
      position: absolute;
      border-radius: 10px;
      transition: all 0.3s ease-in-out;
      box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
      width: 74px;
      height: 115px;
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
  .tarot-message {
    font-size: 12px;
    line-height: 18px;
    color: var(--white);
  }
`;

export { TarotDetail };
