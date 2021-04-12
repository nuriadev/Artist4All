import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-user-settings-account',
  templateUrl: './user-settings-account.component.html',
  styleUrls: ['./user-settings-account.component.css'],
})
export class UserSettingsAccountComponent implements OnInit {
  constructor(
    private _sessionService: SessionService,
    private _userService: UserService
  ) {}

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id: number = this.user.id;
  userEdited: User;

  sliderSwitchPrivate = document.getElementById('sliderSwitchPrivate');
  toggleButtonPrivate = document.getElementById('toggleButtonPrivate');
  staredPrivate: boolean;
  ngOnInit(): void {
    let sliderSwitchPrivate = document.getElementById('sliderSwitchPrivate');
    let toggleButtonPrivate = document.getElementById('toggleButtonPrivate');
    if (this.user.isPrivate == 0) {
      sliderSwitchPrivate.style.marginLeft = '0px';
      toggleButtonPrivate.style.backgroundColor = 'rgba(229, 231, 235)';
      this.isPrivate = false;
      this.staredPrivate = false;
    } else if (this.user.isPrivate == 1) {
      sliderSwitchPrivate.style.marginLeft = '20px';
      toggleButtonPrivate.style.backgroundColor = '#2196F3';
      this.isPrivate = true;
      this.staredPrivate = true;
    }
  }

  isPrivate: boolean;
  alternatePrivateAccount() {
    let sliderSwitchPrivate = document.getElementById('sliderSwitchPrivate');
    let toggleButtonPrivate = document.getElementById('toggleButtonPrivate');
    if (!this.staredPrivate) {
      if (!this.isPrivate) {
        sliderSwitchPrivate.style.transform = 'translateX(20px)';
        toggleButtonPrivate.style.backgroundColor = '#2196F3';
        this.user.isPrivate = 1;
        this.isPrivate = true;
      } else {
        sliderSwitchPrivate.style.transform = 'translateX(0px)';
        toggleButtonPrivate.style.backgroundColor = 'rgba(229, 231, 235)';
        this.user.isPrivate = 0;
        this.isPrivate = false;
      }
    } else {
      if (!this.isPrivate) {
        sliderSwitchPrivate.style.transform = 'translateX(0.40px)';
        toggleButtonPrivate.style.backgroundColor = '#2196F3';
        this.user.isPrivate = 1;
        this.isPrivate = true;
      } else {
        sliderSwitchPrivate.style.transform = 'translateX(-20px)';
        toggleButtonPrivate.style.backgroundColor = 'rgba(229, 231, 235)';
        this.user.isPrivate = 0;
        this.isPrivate = false;
      }
    }
    this._userService.privateAccountSwitcher(this.user, this.token).subscribe(
      (result) => {
        this.userEdited = result.user;
        let userSession = new Session(result.token, this.userEdited);
        this._sessionService.setCurrentSession(userSession);
      },
      (error) => {
        console.log(error);
      }
    );
  }
}
