import { useEffect, useState } from 'react';
import * as Styled from './ProcessBar.styled';
import PendingProcessProps from './ProcessBar.type';

const ProcessBar = ({ pendingProcess }: PendingProcessProps) => {
  if (!pendingProcess.status) return null;

  const { createAt, endDate, serverResponseTime } = pendingProcess;

  const [remainingTime, setRemainingTime] = useState<number>(0);
  const [progressPercentage, setProgressPercentage] = useState<number>(0);
  const [isExpired, setIsExpired] = useState<boolean>(false);

  useEffect(() => {
    if (!createAt || !endDate || !serverResponseTime) return;

    const startTime = new Date(createAt).getTime();
    const finishTime = new Date(endDate).getTime();
    const serverTime = new Date(serverResponseTime).getTime();

    if (serverTime >= finishTime || serverTime < startTime) {
      setIsExpired(true);
      return;
    }

    const totalTime = finishTime - startTime;
    const elapsedTime = serverTime - startTime;

    const initialRemainingTime = (totalTime - elapsedTime) / 1000; // saniye cinsinden
    setRemainingTime(initialRemainingTime);

    const initialProgress = (initialRemainingTime / (totalTime / 1000)) * 100;
    setProgressPercentage(initialProgress);

    const intervalId = setInterval(() => {
      setRemainingTime((prevTime) => {
        const newTime = prevTime - 1;
        if (newTime <= 0) {
          clearInterval(intervalId);
          console.log('Process completed');
          return 0;
        }
        setProgressPercentage((newTime / (totalTime / 1000)) * 100);
        return newTime;
      });
    }, 1000);

    return () => clearInterval(intervalId);
  }, [createAt, endDate, serverResponseTime]);

  if (isExpired) return <></>; // Eğer süresi dolmuşsa bileşeni render etme

  const formatTime = (time: number) => {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds.toString().padStart(2, '0')}`;
  };

  return (
    <Styled.ProcessBar>
      <Styled.ProcessBarInner $progress={progressPercentage}>
        <div className="progress-percentage">{formatTime(remainingTime)}</div>
      </Styled.ProcessBarInner>
    </Styled.ProcessBar>
  );
};

export default ProcessBar;
