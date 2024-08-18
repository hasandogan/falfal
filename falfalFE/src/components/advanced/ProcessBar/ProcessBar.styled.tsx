import styled, { keyframes } from 'styled-components';

interface ProcessBarInnerProps {
  $progress: number;
}

const gradientAnimation = keyframes`
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
`;

const ProcessBarWrapper = styled.div`
  .info {
    font-size: 12px;
  }
  .ad-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-betweeen;
    margin: 20px 0;
    gap: 20px;
    button {
      font-size: 12px;
      padding: 4px 12px;
    }
  }
`;

const ProcessBar = styled.div`
  width: 100%;
  overflow: hidden;
  background: #4e47a6;
  border-top: 1px solid var(--white);
  border-bottom: 1px solid var(--white);
  position: relative;
`;

const ProcessBarInner = styled.div<ProcessBarInnerProps>`
  width: calc(100% - ${({ $progress }) => $progress}%);
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

export { ProcessBar, ProcessBarInner, ProcessBarWrapper };
