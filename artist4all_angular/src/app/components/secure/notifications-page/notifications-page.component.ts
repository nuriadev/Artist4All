import { Component, OnInit } from '@angular/core';
import { Session } from 'src/app/core/models/session';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-notifications-page',
  templateUrl: './notifications-page.component.html',
  styleUrls: ['./notifications-page.component.css']
})
export class NotificationsPageComponent implements OnInit {

  constructor(
    private _sessionService:SessionService,
    private _userService: UserService
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number;

  ngOnInit(): void {
    this.id = this.user.id;
  }

}
