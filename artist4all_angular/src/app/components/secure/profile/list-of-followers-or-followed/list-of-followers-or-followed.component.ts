import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-list-of-followers-or-followed',
  templateUrl: './list-of-followers-or-followed.component.html',
  styleUrls: ['./list-of-followers-or-followed.component.css']
})
export class ListOfFollowersOrFollowedComponent implements OnInit {

  constructor(
    private _userService: UserService,
    private _activeRoute: ActivatedRoute,
    private _sessionService: SessionService
  ) { }

  userlist: Array<User> = [];
  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  username:string;

  typeList:string = '';
  profileUsername:string = '';
  ngOnInit(): void {
    this._activeRoute.paramMap.subscribe(
      (params) => {
        this.profileUsername = params.get('username');
        this.typeList = params.get('typeList');
        if (this.profileUsername == 'my') this.username = this.user.username;
        else this.username = this.profileUsername;
        if (this.typeList == 'followers') {
          this._userService.getFollowers(this.username, this.token).subscribe(
            (result) => {
              this.userlist = result;
            }, (error) => {
              console.error(error);
            }
          )
        } else if (this.typeList == 'followed') {
          this._userService.getUsersFollowed(this.username, this.token).subscribe(
            (result) => {
              this.userlist = result;
            }, (error) => {
              console.error(error);
            }
          )
        }
      }
    );
  }

  //todo: si se puede poner botón de seguir o dejar de
}
