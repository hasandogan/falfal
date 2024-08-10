import styled from 'styled-components';

const FortuneNotFound = styled.div`
  display: flex;
  flex-direction: column;
  gap: 20px;
  .title {
    font-weight: 600;
    font-size: 20px;
  }
  .description {
    font-size: 14px;
  }
  .links {
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: space-between;
  }
`;

export { FortuneNotFound };
