import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-user-settings-profile',
  templateUrl: './user-settings-profile.component.html',
  styleUrls: ['./user-settings-profile.component.css'],
})
export class UserSettingsProfileComponent implements OnInit {
  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _router: Router
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number;
  name: string;
  surname1: string;
  name_2: string;
  surname1_2: string;
  surname2: string;
  email: string;
  username: string;
  password: string;
  isArtist: number;
  imgAvatar: FileList;
  aboutMe: string;
  isHidden: boolean;

  ngOnInit(): void {
    this.id = this.user.id;
    this.name = this.user.name;
    this.surname1 = this.user.surname1;
    this.name_2 = this.user.name;
    this.surname1_2 = this.user.surname1;
    this.surname2 = this.user.surname2;
    this.email = this.user.email;
    this.username = this.user.username;
    this.password = this.user.password;
    this.isArtist = this.user.isArtist;
    this.imgAvatar = this.user.imgAvatar;
    this.aboutMe = this.user.aboutMe;
    this.isHidden = true;
  }

  imgToUpload: FileList = null;
  changeImgAvatar(newImgAvatar: FileList) {
    this.imgToUpload = newImgAvatar;
  }

  userEdited: User;
  edit() {
    this._userService.edit(this.id, this.name, this.surname1, this.surname2, this.email, this.username, this.aboutMe, this.imgToUpload).subscribe(
      (result) => {
        this.userEdited = result.user;
        let userSession = new Session(result.token, this.userEdited);
        this._sessionService.setCurrentSession(userSession);
        location.reload();
      }, (error) => {
        console.log(error);
      });
  }

  showAlert() {
    let alert = (<HTMLInputElement>document.getElementById("alert"));
    if (this.isHidden) {
      alert.classList.remove("hidden");
      this.isHidden = false;
    } else {
      alert.classList.add("hidden");
    }

  }
}
