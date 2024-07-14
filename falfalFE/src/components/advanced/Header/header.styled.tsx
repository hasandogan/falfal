import styled from 'styled-components';

const Header = styled.header`
  background: var(--card-bg);
  border: 2px solid #000000;
  box-shadow: 0px -4px 16px #000000;
  border-radius: 0 0 28px 28px;
  padding: 24px;
  margin-bottom: 24px;
  .general-title {
    font-size: 14px;
    line-height: 20px;
    text-align: center;
    color: #8d8e99;
    margin-bottom: 4px;
  }
  .page-name {
    font-size: 24px;
    line-height: 24px;
    text-align: center;
    color: #ffffff;
  }
  .header-buttons {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
    .header-buttons-left {
      display: flex;
      align-items: center;
      gap: 24px;
      .button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 15px;
        line-height: 20px;
        color: #ffffff;
      }
    }
    .header-buttons-right {
      .profile {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--card-bg);
        border: 2px solid #000000;
        box-shadow: 0px -4px 16px #000000;
        border-radius: 28px;
      }
    }
  }
`;

export { Header };
