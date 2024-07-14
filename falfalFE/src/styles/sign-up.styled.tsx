import styled from 'styled-components';

const SignUpWrapper = styled.div`
  display: flex;
  flex-direction: column;
  gap: 24px;
  .seperator {
    .seperator-text {
      font-size: 14px;
      line-height: 17px;
      color: #8d8e99;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      white-space: nowrap;
      &:before {
        content: '';
        height: 1px;
        width: 100%;
        background: #8d8e99;
        display: inline-block;
      }
      &:after {
        content: '';
        height: 1px;
        width: 100%;
        background: #8d8e99;
        display: inline-block;
      }
    }
  }
`;

const GoogleLogin = styled.a`
  height: 54px;
  background: #ffffff;
  box-shadow:
    0px 0px 3px rgba(0, 0, 0, 0.084),
    0px 2px 3px rgba(0, 0, 0, 0.168);
  border-radius: 10px;
  padding: 16px;
  font-size: 15px;
  line-height: 18px;
  color: rgba(0, 0, 0, 0.54);
  display: flex;
  align-items: center;
  gap: 16px;
`;

const AppleLogin = styled.a`
  height: 54px;
  background: #000000;
  box-shadow:
    0px 0px 3px rgba(0, 0, 0, 0.084),
    0px 2px 3px rgba(0, 0, 0, 0.168);
  border-radius: 10px;
  padding: 16px;
  font-size: 15px;
  line-height: 18px;
  color: #ffffff;
  display: flex;
  align-items: center;
  gap: 16px;
`;

const ActionLink = styled.div`
  text-align: center;
  margin-top: 24px;
  font-size: 12px;
  line-height: 15px;
  color: #8d8e99;
  a {
    color: #11dce8;
    display: inline-block;
    margin-left: 4px;
  }
`;

export { ActionLink, AppleLogin, GoogleLogin, SignUpWrapper };
