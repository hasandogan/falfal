import PendingProcessProps from './ProcessBar.type';

const ProcessBar = ({ pendingProcess }: PendingProcessProps) => {
  if (!pendingProcess.status) {
    return <></>;
  }

  const progress = 20;

  return (
    <div
      style={{
        width: '100%',
        backgroundColor: '#f3f3f3',
        borderRadius: '5px',
        overflow: 'hidden',
      }}
    >
      <div
        style={{
          width: `${progress}%`,
          backgroundColor: '#4caf50',
          height: '30px',
          transition: 'width 0.5s ease',
        }}
      />
    </div>
  );
};

export default ProcessBar;
