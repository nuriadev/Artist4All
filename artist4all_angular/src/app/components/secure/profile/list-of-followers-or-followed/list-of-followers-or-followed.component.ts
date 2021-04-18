import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-list-of-followers-or-followed',
  templateUrl: './list-of-followers-or-followed.component.html',
  styleUrls: ['./list-of-followers-or-followed.component.css'],
})
export class ListOfFollowersOrFollowedComponent implements OnInit {
  constructor(
    private _userService: UserService,
    private _activeRoute: ActivatedRoute,
    private _sessionService: SessionService
  ) {}

  userlist: Array<User> = [];
  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number;

  typeList: string = '';
  id_user: string = '';
  ngOnInit(): void {
    this._activeRoute.paramMap.subscribe((params) => {
      this.id_user = params.get('id_user');
      this.typeList = params.get('typeList');
      if (parseInt(this.id_user) == this.user.id) this.id = this.user.id;
      else this.id = parseInt(this.id_user);
      if (this.typeList == 'followers') {
        this._userService.getFollowers(this.id).subscribe(
          (result) => {
            this.userlist = result;
          }, (error) => {
            console.error(error);
        });
      } else if (this.typeList == 'followed') {
        this._userService.getFollowed(this.id).subscribe(
          (result) => {
            this.userlist = result;
          }, (error) => {
            console.error(error);
        });
      }
    });
  }

  //todo: si se puede poner botón de seguir o dejar de
}
