import styled, { keyframes } from 'styled-components';

interface ProcessBarInnerProps {
  $progress: number;
}

const gradientAnimation = keyframes`
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
`;

const ProcessBar = styled.div`
  width: 100%;
  border-radius: 5px;
  overflow: hidden;
  background: #4e47a6;
  margin-bottom: 20px;
  border: 1px solid var(--white);
  position: relative;
`;

const ProcessBarInner = styled.div<ProcessBarInnerProps>`
  width: ${({ $progress }) => $progress}%;
  background: linear-gradient(270deg, #1a1c40, #3a3b6c, #1a1c40);
  background-size: 400% 400%;
  height: 30px;
  animation: ${gradientAnimation} 3s ease infinite;
  transition: width 0.5s ease;

  .progress-percentage {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 0;
    right: 0;
    margin: 0 auto;
    text-align: center;
    color: var(--white);
  }
`;

export { ProcessBar, ProcessBarInner };
