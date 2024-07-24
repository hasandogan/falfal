import styled from 'styled-components';

const Container = styled.div`
  display: flex;
  flex-direction: column;
`;

const Select = styled.select`
  background: #091522;
  border: 2px solid #ffffff;
  border-radius: 44px;
  outline: none;
  padding: 14px;
  width: 100%;
  font-weight: 400;
  font-size: 16px;
  line-height: 18px;
  color: var(--white);

  &::placeholder {
    font-weight: 400;
    font-size: 16px;
    line-height: 18px;
    color: var(--white);
  }
`;

export { Container, Select };
