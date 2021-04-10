import { User } from "./user";

export class Notification {
  id:number;
  user_responsible:User;
  user_receiver:User;
  bodyNotification:string;
  isRead:number;
  typeNotification:string;
  notification_date:Date;

  constructor(
  id:number,
  user_responsible:User,
  user_receiver:User,
  bodyNotification:string,
  isRead:number,
  typeNotification:string,
  notification_date:Date) {
      this.id = id;
      this.user_responsible = user_responsible;
      this.user_receiver = user_receiver;
      this.bodyNotification = bodyNotification;
      this.isRead = isRead;
      this.typeNotification = typeNotification;
      this.notification_date = notification_date;
  }
}
