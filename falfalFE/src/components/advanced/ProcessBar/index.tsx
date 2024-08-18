import { useEffect, useState } from 'react';
import * as Styled from './ProcessBar.styled';
import PendingProcessProps from './ProcessBar.type';

const ProcessBar = ({ pendingProcess }: PendingProcessProps) => {
  if (!pendingProcess.status) {
    return <></>;
  }

  const { createAt, endDate, serverResponseTime } = pendingProcess;

  const [progress, setProgress] = useState<number>(0);

  useEffect(() => {
    if (createAt === null || endDate === null || serverResponseTime === null) {
      return;
    }
    const startTime = new Date(createAt).getTime();
    const finishTime = new Date(endDate).getTime();
    const serverTime = new Date(serverResponseTime).getTime();

    const totalTime = finishTime - startTime;
    const elapsedTime = serverTime - startTime;

    const initialProgress = (elapsedTime / totalTime) * 100;
    setProgress(initialProgress);

    const intervalId = setInterval(() => {
      setProgress((prevProgress) => {
        const newProgress = prevProgress + (100 / totalTime) * 1000;

        if (newProgress >= 100) {
          clearInterval(intervalId);
          console.log('Progress reached 100%');
          return 100;
        }

        return Math.min(newProgress, 100);
      });
    }, 1000);

    return () => clearInterval(intervalId);
  }, [createAt, endDate, serverResponseTime]);
  return (
    <Styled.ProcessBar>
      <Styled.ProcessBarInner $progress={progress}>
        <div className={'progress-percentage'}>{progress.toFixed(2)}</div>
      </Styled.ProcessBarInner>
    </Styled.ProcessBar>
  );
};

export default ProcessBar;
