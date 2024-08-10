import styled from 'styled-components';
import Card from '../../Card';

const FortuneCard = styled(Card)`
  margin-bottom: 20px;
  .header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    .title {
      font-size: 22px;
      font-weight: 600;
    }
    .date {
      font-size: 10px;
    }
  }
  .message {
    margin-bottom: 20px;
    font-size: 16px;
  }
  .read-more {
    font-size: 16px;
  }
`;

export { FortuneCard };
