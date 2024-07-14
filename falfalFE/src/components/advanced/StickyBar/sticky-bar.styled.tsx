import styled from 'styled-components';

const StickyBar = styled.div`
  padding: 15px;
  display: flex;
  align-items: center;
  justify-content: space-around;
  background: var(--card-bg);
  box-shadow: 0px -4px 16px #000000;
  border-radius: 28px;
  position: fixed;
  bottom: 20px;
  left: 0;
  right: 0;
  margin: 0 auto;
`;

export { StickyBar };
