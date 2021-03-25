import { Component, OnInit } from '@angular/core';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-provisional-add-user',
  templateUrl: './provisional-add-user.component.html',
  styleUrls: ['./provisional-add-user.component.css']
})
export class ProvisionalAddUserComponent implements OnInit {

  constructor(
    private _userService: UserService,
    private _sessionService: SessionService
  ) { }

  userlist: Array<UserService> = [];
  user = this._sessionService.getCurrentUser();

  ngOnInit(): void {
    this._userService.getOtherUsers(this.user.username).subscribe(
      (result) => {
        this.userlist = result;
      }, (error) => {
        console.error(error);
      }
    )
  }

}
