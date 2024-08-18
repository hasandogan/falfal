import Button from '../../basic/Button';
import ProcessBarLogic from './ProcessBar.logic';
import * as Styled from './ProcessBar.styled';
import PendingProcessProps from './ProcessBar.type';

const ProcessBar = ({ pendingProcess }: PendingProcessProps) => {
  if (!pendingProcess.status) return null;

  const {
    progressPercentage,
    remainingTime,
    handleAdClick,
    formatTime,
    isExpired,
  } = ProcessBarLogic({ pendingProcess });

  if (isExpired) return <></>;

  return (
    <Styled.ProcessBarWrapper>
      <div className="info">
        Falını özenle hazırlıyoruz, ama birazcık sabretmen gerekecek. Aşağıdaki
        sayaç tamamlandığında, falın hazır olacak. Eğer falına daha hızlı
        ulaşmak istersen, reklam izle butonuna tıklayabilirsin.
      </div>
      <div className="ad-wrapper">
        <Styled.ProcessBar>
          <Styled.ProcessBarInner $progress={progressPercentage}>
            <div className="progress-percentage">
              {formatTime(remainingTime)}
            </div>
          </Styled.ProcessBarInner>
        </Styled.ProcessBar>
        <Button onClick={handleAdClick}>Reklam</Button>
      </div>
    </Styled.ProcessBarWrapper>
  );
};

export default ProcessBar;
