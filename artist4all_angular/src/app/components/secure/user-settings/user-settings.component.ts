import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-user-settings',
  templateUrl: './user-settings.component.html',
  styleUrls: ['./user-settings.component.css'],
  providers: [UserService]
})
export class UserSettingsComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
  }

}
