import styled from 'styled-components';

const TarotCard = styled.div`
  background-color: #2c3055;
  background-image: url('/images/card.png');
  background-position: center center;
  background-size: cover;
  position: absolute;
  width: 50px;
  height: 70px;
  border-radius: 10px;
  transition: all 0.3s ease-in-out;
  box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
  &.selected {
    margin-top: -15px;
  }
`;

export { TarotCard };
