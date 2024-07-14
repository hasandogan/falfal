import styled from 'styled-components';

const ProfileWrapper = styled.div`
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

export { ActionLink, ProfileWrapper };
