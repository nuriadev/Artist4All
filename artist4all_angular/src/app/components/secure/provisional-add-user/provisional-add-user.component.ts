import { Component, OnInit } from '@angular/core';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-provisional-add-user',
  templateUrl: './provisional-add-user.component.html',
  styleUrls: ['./provisional-add-user.component.css'],
})
export class ProvisionalAddUserComponent implements OnInit {
  constructor(
    private _userService: UserService,
    private _sessionService: SessionService
  ) {}

  userlist: Array<User> = [];
  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  ngOnInit(): void {
    this._userService.getAllOtherUsers(this.user.id, this.token).subscribe(
      (result) => {
        this.userlist = result;
      },
      (error) => {
        console.error(error);
      }
    );
  }
}
