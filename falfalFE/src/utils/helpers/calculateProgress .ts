export const calculateProgress = (
  startTime: number,
  endTime: number,
  currentTime: number
) => {
  const totalDuration = endTime - startTime;
  const elapsedTime = currentTime - startTime;
  return Math.max(0, Math.min(100, (elapsedTime / totalDuration) * 100));
};
